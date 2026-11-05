#!/usr/bin/env python3
"""Find all PHP class/trait/interface/enum definitions and detect case-sensitive collisions."""

import os
import re
import sys
from collections import defaultdict

# Regex to match class/trait/interface/enum declarations
CLASS_PATTERN = re.compile(
    r'^\s*(?:abstract\s+|final\s+)*(?:class|trait|interface|enum)\s+(\w+)',
    re.MULTILINE
)

def find_php_files(root_dir):
    php_files = []
    for dirpath, dirnames, filenames in os.walk(root_dir):
        dirnames[:] = [d for d in dirnames if d != 'vendor' and not d.startswith('.')]
        for f in filenames:
            if f.endswith('.php'):
                php_files.append(os.path.join(dirpath, f))
    return php_files

def extract_class_names(filepath):
    names = []
    try:
        with open(filepath, 'r', encoding='utf-8', errors='replace') as f:
            content = f.read()
        for match in CLASS_PATTERN.finditer(content):
            names.append(match.group(1))
    except Exception as e:
        print(f"Error reading {filepath}: {e}", file=sys.stderr)
    return names

def find_case_sensitive_duplicates(all_definitions):
    by_lower = defaultdict(list)
    for filepath, classname in all_definitions:
        by_lower[classname.lower()].append((filepath, classname))
    
    duplicates = {}
    for lower_name, entries in by_lower.items():
        unique_cases = set(cased for _, cased in entries)
        if len(unique_cases) > 1:
            duplicates[lower_name] = entries
    return duplicates

def find_same_filename_different_case(root_dir):
    issues = []
    for dirpath, dirnames, filenames in os.walk(root_dir):
        dirnames[:] = [d for d in dirnames if d != 'vendor' and not d.startswith('.')]
        seen = {}
        for f in filenames:
            lower = f.lower()
            if lower in seen:
                issues.append((dirpath, seen[lower], f))
            else:
                seen[lower] = f
    return issues

def main():
    laravel_dir = '/var/www/_bases/base_roosko_fila5/laravel'
    
    print("=" * 80)
    print("PHP CLASS NAME COLLISION ANALYSIS")
    print("=" * 80)
    
    print("\n[1] Scanning for PHP files...")
    php_files = find_php_files(laravel_dir)
    print(f"    Found {len(php_files)} PHP files (excluding vendor/)")
    
    print("\n[2] Extracting class/trait/interface/enum definitions...")
    all_definitions = []
    for filepath in php_files:
        names = extract_class_names(filepath)
        for name in names:
            all_definitions.append((filepath, name))
    print(f"    Found {len(all_definitions)} type declarations")
    
    print("\n[3] Checking for case-sensitive duplicates...")
    duplicates = find_case_sensitive_duplicates(all_definitions)
    
    if duplicates:
        print(f"\n    *** FOUND {len(duplicates)} CASE-SENSITIVE COLLISIONS ***\n")
        for lower_name, entries in sorted(duplicates.items()):
            print(f"    Collision: '{lower_name}'")
            for filepath, classname in entries:
                rel_path = os.path.relpath(filepath, laravel_dir)
                print(f"      - {classname} -> {rel_path}")
            print()
    else:
        print("    No case-sensitive class name collisions found.")
    
    print("\n[4] Checking for files with same name but different case...")
    filename_issues = find_same_filename_different_case(laravel_dir)
    if filename_issues:
        print(f"\n    *** FOUND {len(filename_issues)} FILENAME CASE COLLISIONS ***\n")
        for dirpath, f1, f2 in filename_issues:
            rel_dir = os.path.relpath(dirpath, laravel_dir)
            print(f"    Directory: {rel_dir}")
            print(f"      - {f1}")
            print(f"      - {f2}")
            print()
    else:
        print("    No filename case collisions found.")
    
    print("\n" + "=" * 80)
    print("SUMMARY")
    print("=" * 80)
    print(f"  Total PHP files scanned:     {len(php_files)}")
    print(f"  Total type declarations:    {len(all_definitions)}")
    print(f"  Case-sensitive collisions:  {len(duplicates)}")
    print(f"  Filename case collisions:   {len(filename_issues)}")
    
    return 0 if not duplicates and not filename_issues else 1

if __name__ == '__main__':
    sys.exit(main())