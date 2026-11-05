#!/usr/bin/env python3
"""
HTML Body Structure Comparator

Compares body HTML structure (excluding scripts and presentation attributes)
between two URLs to identify structural differences. Useful for regression testing
when HTML layout changes or for comparing reference implementations.

Usage:
    python3 compare-html.py [url1] [url2]

    url1: Reference URL (default: Italia design comuni homepage)
    url2: Local URL to compare (default: local test server)

Exit Codes:
    0: Structures match
    1: Structures differ
"""
import sys
import urllib.request
from html.parser import HTMLParser

# Default URLs for comparison
URL_REF = "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html"
URL_LOCAL = "http://127.0.0.1:8000/it/tests/homepage"


class BodyExtractor(HTMLParser):
    """
    Extracts HTML body structure excluding scripts and presentation attributes.

    Attributes:
        in_body (bool): Whether parser is currently inside body tag
        in_script (bool): Whether parser is inside script tag
        depth (int): Current nesting depth
        elements (list): Parsed elements as tuples of (kind, tag, attrs_dict)
    """

    def __init__(self):
        """Initialize the HTML parser."""
        super().__init__()
        self.in_body = False
        self.in_script = False
        self.depth = 0
        self.elements = []  # list of (tag, attrs_dict)

    def handle_starttag(self, tag, attrs):
        """Process opening tags, extracting only body content and ignoring scripts."""
        if tag == "body":
            self.in_body = True
            return
        if not self.in_body:
            return
        if tag == "script":
            self.in_script = True
            return
        if not self.in_script:
            # Exclude presentation attributes (class, style, id)
            attrs_dict = {k: v for k, v in attrs if k not in ("class", "style", "id")}
            self.elements.append(("open", tag, attrs_dict))

    def handle_endtag(self, tag):
        """Process closing tags, tracking body and script sections."""
        if tag == "script":
            self.in_script = False
            return
        if not self.in_body or self.in_script:
            return
        if tag == "body":
            self.in_body = False
            return
        self.elements.append(("close", tag))

    def handle_data(self, data):
        """Extract text content from body (first 80 characters)."""
        if not self.in_body or self.in_script:
            return
        text = data.strip()
        if text:
            self.elements.append(("text", text[:80]))


def fetch(url):
    """
    Fetch HTML content from URL with proper error handling.

    Args:
        url (str): URL to fetch

    Returns:
        str: HTML content

    Raises:
        urllib.error.URLError: If URL cannot be reached
    """
    req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
    with urllib.request.urlopen(req, timeout=15) as r:
        return r.read().decode("utf-8", errors="replace")


def extract(html):
    """
    Extract structured elements from HTML body.

    Args:
        html (str): HTML content to parse

    Returns:
        list: Parsed elements
    """
    p = BodyExtractor()
    p.feed(html)
    return p.elements


def tag_sequence(elements):
    """
    Extract just opening tags in order (structural skeleton).

    Args:
        elements (list): Parsed elements from extract()

    Returns:
        list: Sequence of opening tags like ['<div>', '<header>', ...]
    """
    return [f"<{tag}>" for kind, tag, *_ in elements if kind == "open"]


def compare(url1, url2):
    """
    Compare HTML structures between two URLs.

    Performs comprehensive structural comparison including:
    - Tag count differences
    - Sequential structure mismatches
    - Length differences

    Args:
        url1 (str): Reference URL
        url2 (str): Local URL to compare

    Returns:
        bool: True if structures match, False otherwise
    """
    print(f"Fetching REF:   {url1}")
    html1 = fetch(url1)
    print(f"Fetching LOCAL: {url2}")
    html2 = fetch(url2)

    e1 = extract(html1)
    e2 = extract(html2)

    s1 = tag_sequence(e1)
    s2 = tag_sequence(e2)

    print(f"\nREF   tags: {len(s1)}")
    print(f"LOCAL tags: {len(s2)}")

    # Find differences
    set1 = {}
    set2 = {}
    for t in s1:
        set1[t] = set1.get(t, 0) + 1
    for t in s2:
        set2[t] = set2.get(t, 0) + 1

    all_tags = sorted(set(set1) | set(set2))
    diffs = [(t, set1.get(t, 0), set2.get(t, 0)) for t in all_tags if set1.get(t, 0) != set2.get(t, 0)]

    if not diffs:
        print("\n✅ TAG COUNTS MATCH - same structural skeleton")
    else:
        print(f"\n❌ {len(diffs)} TAG COUNT DIFFERENCES:")
        print(f"{'TAG':<30} {'REF':>6} {'LOCAL':>6} {'DIFF':>6}")
        print("-" * 50)
        for tag, c1, c2 in sorted(diffs, key=lambda x: abs(x[1]-x[2]), reverse=True):
            diff = c2 - c1
            sign = "+" if diff > 0 else ""
            print(f"{tag:<30} {c1:>6} {c2:>6} {sign}{diff:>5}")

    # Sequential diff (first N mismatches)
    print("\n--- SEQUENTIAL STRUCTURE DIFF (first 20 mismatches) ---")
    mismatches = 0
    for i, (t1, t2) in enumerate(zip(s1, s2)):
        if t1 != t2:
            print(f"  pos {i:4d}: REF={t1:<25} LOCAL={t2}")
            mismatches += 1
            if mismatches >= 20:
                print("  ... (truncated)")
                break
    if mismatches == 0:
        print("  ✅ Sequential order matches (up to min length)")

    # Length diff
    if len(s1) != len(s2):
        extra = len(s1) - len(s2)
        if extra > 0:
            print(f"\n  REF has {extra} extra tags at end: {s1[len(s2):len(s2)+10]}")
        else:
            print(f"\n  LOCAL has {-extra} extra tags at end: {s2[len(s1):len(s1)+10]}")

    return len(diffs) == 0 and len(s1) == len(s2)


if __name__ == "__main__":
    url1 = sys.argv[1] if len(sys.argv) > 1 else URL_REF
    url2 = sys.argv[2] if len(sys.argv) > 2 else URL_LOCAL
    ok = compare(url1, url2)
    sys.exit(0 if ok else 1)
