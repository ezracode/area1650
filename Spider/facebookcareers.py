from lxml import html
from collections import OrderedDict
import requests

link = "https://www.facebook.com/careers/teams/engineering/"

response = requests.get(link) #get page data from server, block redirects
sourceCode = response.content #get string of source code from response
htmlElem = html.document_fromstring(sourceCode) #make HTML element object

aDict = {}

tdElems = htmlElem.iterlinks()
for elem in tdElems:
    if str(elem).find("careers/jobs") != -1: 
    #or str(elem).find("/wiki/Clasifica") != -1 and str(elem).find("_Campeonato_Concacaf_de_") != -1 
    #or str(elem).find("/wiki/Campeonato_Concacaf_de_") != -1) :
        position = len(elem[2]) - 19
        ckey = elem[2][position:]
        print (elem)
        aDict[ckey] = elem[2]

print ("**********************************")

#print (aDict)

aDict = OrderedDict(sorted(aDict.items()))
w = 0
for k in aDict:
    w = w + 1
    print (w, k, aDict[k])