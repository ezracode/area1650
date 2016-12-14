import re, urllib.request

textfile = open('depth_1.txt','wt')
print ("Enter the URL you wish to crawl..")
print ('Usage  - "http://phocks.org/stumble/creepy/" <-- With the double quotes')
myurl = input("@> ")
print (myurl)
f = urllib.request.urlopen(myurl)
h = f.readlines()
for line in h:
	for i in re.findall('''href=["']http://(.[^"']+)["']''', str(line), re.I):
		print (i)  
		g = urllib.request.urlopen("http://" + i + "/")
		k = g.readlines()
		for line1 in k:
			for ee in re.findall('''href=["']http://(.[^"']+)["']''', str(line1), re.I):
				print ("http://" + ee + "/")
				textfile.write(ee+'\n')
textfile.close()