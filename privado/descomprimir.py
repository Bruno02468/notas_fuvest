#!/usr/bin/env python3
# bruno borges paschoalinoto 2020
# com agradecimentos a @pieroxy e @eduardtomasek

import sys, lzstring

if len(sys.argv) < 2:
  sys.exit()

s = sys.argv[1].replace("_", "/").replace("-", "+").replace("!", "=");
print(lzstring.LZString().decompresFromBase64(s), end="")
