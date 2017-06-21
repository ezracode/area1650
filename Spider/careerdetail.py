from lxml import html
from lxml import etree
from collections import OrderedDict
import requests
from ezraLibrary import textOfDiv

link = "https://www.facebook.com/careers/jobs/a0I1200000JY01QEAT/"

response = requests.get(link) #get page data from server, block redirects
sourceCode = response.content #get string of source code from response
htmlElem = html.document_fromstring(sourceCode) #make HTML element object

aDict = {}

root = etree.HTML(sourceCode)
#root.findall(".//table")
aDict = {}
for e1 in root.iterfind(".//div"):
    texto = textOfDiv(e1)
    palabras = texto.split()
    for palabra in  palabras:
        aDict[palabra] = palabra

for item in aDict:
    print (item.encode('utf-8'))    
