#/usr/bin/bash

# get price list from mtg goldfish
python3 /home/Ophidian/work/make_price_list/get_price_list.py

# make personal price list
/usr/bin/php /home/Ophidian/work/make_price_list/make_price_list.php

# move personal price list to update
cp /home/mo-bot/work/my_price_list.txt '/cygdrive/c/Program Files (x86)/MTGOLibrary/MTGO Library Bot/prices/PersonalPrices.txt'
