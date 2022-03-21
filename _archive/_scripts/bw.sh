mkdir bw colour

echo "• Trim"
#cp original/*.png colour
cd colour
find *.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"

	#echo NAME: "$NAME"
	###echo ""
	#if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  #echo "$NAME"
	#fi

	#convert "$f" -trim "$NAME.png"
done

echo "• BW"
cd ../
cp colour/*.png bw
cd bw
find *.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"

	#echo NAME: "$NAME"
	###echo ""

	if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  echo "$NAME"
	fi

	convert "$f" -colorspace gray \
        \( +clone -blur 0x2 \) +swap -compose divide -composite \
        -linear-stretch 5%x0% "$NAME.png"
done