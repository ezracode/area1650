from lxml import html
from lxml import etree
from collections import OrderedDict

def textOfDiv( myContainer ):
    myText = ""
    myResponse = ""
    for myElement in myContainer:
        if myElement.text is not None:
           myText = myElement.text
        else:
           myText = textOfDiv( myElement )
        myResponse = myResponse + " " +  myText
    return myResponse