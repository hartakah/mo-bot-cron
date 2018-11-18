################################################################
### scrayping Magic Online price List from mtggoldfish 
###
### input  : https://www.mtggoldfish.com
### output : goldfish_price.list (csv file)
################################################################

from pyquery import PyQuery
from lxml import etree
import urllib

# get html data
pq=PyQuery(url='https://www.mtggoldfish.com/index/GRN#online')

# initialize
card_data = []
label_list=[]
i=0
col_num = 0

path='/home/mo-bot/work/goldfish_price_list.tsv' # output file

# main loop
for table in pq('.tablesorter-bootstrap-popover-online')('table'):
    with open(path, mode='w') as f:
        for tr in pq(table)('tr'):
            # get label from 1st line
            if(i==0):
                for label in pq(tr)('th'):
                    if col_num != 0:
                        f.write('\t')
                    f.write(pq(label).text())
                    col_num+=1
                i+=1
                col_num+=1
                f.write("\n")
                continue
            # get card data
            j = 0
            col_num = 0
            for td in pq(tr)('td'):
                if col_num != 0:
                    f.write('\t')
                f.write(pq(td).text())
                col_num+=1
                j+=1
            f.write("\n")
