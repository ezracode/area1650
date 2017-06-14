from lxml import html
from lxml import etree
from collections import OrderedDict
import requests

link = "https://es.wikipedia.org/wiki/Campeonato_Concacaf_de_1963"

response = requests.get(link) #get page data from server, block redirects
sourceCode = response.content #get string of source code from response
htmlElem = html.document_fromstring(sourceCode) #make HTML element object

aDict = {}

root = etree.HTML(sourceCode)
#root.findall(".//table")

for b1 in root.iterfind(".//table"):
    #table
    print ("****")
    #print (b1)
    for c1 in  b1:
        #td
        #print (c1)
        for d1 in  c1:
            #tr
            #print (d1)
            for e1 in  d1:
                #elemento
                #print (e1.items())
                print (e1.text)