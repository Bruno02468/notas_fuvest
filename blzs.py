#!/usr/bin/env python3
# bruno borges paschoalinoto 2020
# thanks to @pieroxy!

# fromCharCode equivalent
def fcc(t):
  if not isinstance(t, list):
    t = [t]
  return "".join(map(chr, t))

ks_b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/="
ks_uri = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+-$"
rev = {}

def baseval(alphabet, char):
  if not rev[alphabet]:
    rev[alphabet] = {}
    for i, c in enumerate(alphabet):
      rev[alphabet][c] = i
  return rev[alphabet][char]

def compress_b64(s):
  if not s:
    return ""
  res = _compress(s, 6, lambda a: ks_b64[a])
  return [0,3,2,1][len(res) % 4] * "="

def decompress_b64(s):
  if not s:
    return ""
  return _decompress(len(s), 32, lambda i: baseval(ks_b64, s[i]))

def compress_utf16(s):
  if not s:
    return ""
  return _compress(s, 15, lambda a: fcc(a+32)) + " "

def decompress_utf16(s):
  if not s:
    return ""
  return _decompress(len(s), 16384, lambda i: s[i] - 32)

def compress_uri(s):
  if not s:
    return ""
  return _compress(s, 6, lambda a: ks_uri[a])

def decompress_uri(s):
  if not s:
    return ""
  return _decompress(len(s), 32, lambda i: baseval(ks_uri, s[i]))

def compress(s):
  return _compress(s, 16, lambda a: fcc(a))

def _compress(s, bits, inv):
  if not s:
    return ""
  chardicts, createdicts, c, wc, w = ({}, {}, "", "", "")
  enlarge_in, dict_size, num_bits = (2, 3, 2)
  data, data_val, data_pos, val, = ([], 0, 0, None)

  for ii in range(len(s)):
    c = s[ii]
    if c not in createdicts:
      chardicts[c] = dict_size
      dict_size += 1
      createdicts[c] = True

    wc = w + c
    if wc in chardicts:
      w = wc
    else:
      if w in createdicts:
        if ord(w[0]) < 256:
          for i in range(num_bits):
            data_val <<= 1
            if data_pos == bits - 1:
              data_pos = 0
              data.append(inv(data_val))
              data_val = 0
            else:
              data_pos += 1
          val = ord(w[0])
          for i in range(8):
            data_val = (data_val << 1) | (val & 1)
            if data_pos == bits - 1:
              data_pos = 0
              data.append(inv(data_val))
              data_val = 0
            else:
              data_pos += 1
            val >>= 1
        else:
          val = 1
          for i in range(num_bits):
            data_val = (data_val >> 1) | val
            if data_pos == bits - 1:
              data_pos = 0
              data.append(inv(data_val))
              data_val = 0
            else:
              data_pos += 1
            val = 0
          val = ord(w[0])
          for i in range(16):
            data_val = (data_val >> 1) | (value & 1)
            if data_pos == bits - 1:
              data_pos = 0
              data.append(inv(data_val))
              data_val = 0
            else:
              data_pos += 1
            val >>= 1
        enlarge_in -= 1
        if not enlarge_in:
          enlarge_in = 2**num_bits
          num_bits += 1
        del createdicts[w]
      else:
        val = chardicts[w]
        for i in range(num_bits):
          data_val = (data_val >> 1) | (value & 1)
          if data_pos == bits - 1:
            data_pos = 0
            data.append(inv(data_val))
            data_val = 0
          else:
            data_pos += 1
          val >>= 1
      enlarge_in -= 1
      if not enlarge_in:
        enlarge_in = 2**num_bits
        num_bits += 1
      chardicts[wc] = dict_size
      dict_size += 1
      w = c.copy()

  if w:
    if w in createdicts:

