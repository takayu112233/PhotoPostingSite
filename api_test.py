import requests

url = 'https://maps.googleapis.com/maps/api/place/textsearch/json'
q = {'query': 'smallworlds',
     'language': 'ja',
     'key': 'AIzaSyD7ETzwQaGboRO7zRkC3rMLlEYvEBmssiQ'}


s = requests.Session()
s.headers.update({'Referer': 'www.monotalk.xyz/example'})
r = s.get(url, params=q)
json_o = r.json()
print(json_o)