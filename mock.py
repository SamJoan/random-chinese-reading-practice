import feedparser
import random

MAX_PAGE = 8
page_nb = random.randint(1, MAX_PAGE)

url = 'http://chinesereadingpractice.com/category/beginner/feed/?paged=%s' % page_nb
d = feedparser.parse(url)

entries = []
for entry in d['entries']:
    entries.append(entry['link'])

entry_nb = random.randint(0, len(entries) - 1)
print(entries[entry_nb])
