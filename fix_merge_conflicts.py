#!/usr/bin/env python3
"""Resolve git merge conflicts in PHP files by keeping the HEAD (top) side."""

import re
import os


def resolve_conflicts(content):
    """Remove merge conflict markers, keeping the HEAD (first) side."""
    lines = content.split("\n")
    result = []
    i = 0
    while i < len(lines):
        line = lines[i]
        if line.strip().startswith("<<<<<<< HEAD"):
            # Found a conflict - skip HEAD marker and collect HEAD content
            i += 1
            head_lines = []
            while i < len(lines) and not lines[i].strip().startswith("======="):
                head_lines.append(lines[i])
                i += 1
            # Skip ======= line
            i += 1
            # Skip other side content until >>>>>>>
            while i < len(lines) and not lines[i].strip().startswith(">>>>>>>"):
                i += 1
            # Skip >>>>>>> marker
            i += 1
            # Add only the HEAD content
            result.extend(head_lines)
        else:
            result.append(line)
            i += 1
    return "\n".join(result)


def main():
    import sys

    files_list = sys.argv[1] if len(sys.argv) > 1 else "/tmp/conflict_files.txt"

    with open(files_list) as f:
        files = [line.strip() for line in f if line.strip()]

    # Filter for actual PHP files only
    files = [f for f in files if f.endswith(".php")]

    count = 0
    for filepath in files:
        if not os.path.isfile(filepath):
            continue
        try:
            with open(filepath, "r") as f:
                content = f.read()

            if "<<<<<<< HEAD" not in content:
                continue

            resolved = resolve_conflicts(content)

            with open(filepath, "w") as f:
                f.write(resolved)

            count += 1
            if count % 100 == 0:
                print(f"Fixed {count} files...")
        except Exception as e:
            print(f"Error processing {filepath}: {e}")

    print(f"Fixed {count} files with merge conflicts.")


if __name__ == "__main__":
    main()