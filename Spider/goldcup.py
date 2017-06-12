from lxml import html
from collections import OrderedDict
import requests

link = "https://es.wikipedia.org/wiki/Copa_Oro_de_la_Concacaf"

response = requests.get(link) #get page data from server, block redirects
sourceCode = response.content #get string of source code from response
htmlElem = html.document_fromstring(sourceCode) #make HTML element object

aDict = {}

tdElems = htmlElem.iterlinks()
for elem in tdElems:
    if (str(elem).find("/wiki/Copa_de_Oro_de_la_Concacaf_") != -1 
    or str(elem).find("/wiki/Clasifica") != -1 and str(elem).find("_Campeonato_Concacaf_de_") != -1 
    or str(elem).find("/wiki/Campeonato_Concacaf_de_") != -1) :
        position = len(elem[2]) - 4
        ckey = elem[2][position:]
        print (ckey)
        aDict[ckey] = elem[2]


print ("**********************************")

#print (aDict)

aDict = OrderedDict(sorted(aDict.items()))

for k in aDict:
    print (k, aDict[k])
