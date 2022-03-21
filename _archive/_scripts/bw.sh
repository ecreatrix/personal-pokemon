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
rm *-bw.png
find *-colour.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"
	NAME="${NAME%-colour}"

	#echo NAME: "$NAME"
	###echo ""

	if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  echo "$NAME"
	fi

	convert "$f" \
		-fuzz 20% -fill transparent -opaque white \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-colorspace gray -level 0%,100%,0.1 \
		-fuzz 10% -fill transparent -opaque "#BEBEBE" \
		-fuzz 16% -fill transparent -opaque white "$NAME-2.png"
done

find *-colour.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"
	NAME="${NAME%-colour}"

	#echo NAME: "$NAME"
	###echo ""

	if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  echo "$NAME"
	fi

	convert "$f" \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-fuzz 8% -fill transparent -opaque white \
		-colorspace gray -level 0%,100%,0.3  "$NAME-grey8-3.png"

	convert "$f" \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-fuzz 8% -fill transparent -opaque white \
		-colorspace gray -level 0%,100%,0.1  "$NAME-grey8-1.png"
	
	convert "$f" \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-fuzz 10% -fill transparent -opaque white \
		-colorspace gray -level 0%,100%,0.3  "$NAME-grey10-3.png"
	
	convert "$f" \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-fuzz 10% -fill transparent -opaque white \
		-colorspace gray -level 0%,100%,0.1  "$NAME-grey10-1.png"
done

rm -rf * && cp ../colour/*Gmax.png ../gmax
find *.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"
	NAME="${NAME%-colour}"

	#echo NAME: "$NAME"
	###echo ""

	if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  echo "$NAME"
	fi
	
	convert "$f" \
		-fuzz 20% -fill transparent -opaque white \
		\( +clone -blur 0x2 \) +swap -compose divide -composite \
    	-linear-stretch 6%x0%  \
		-colorspace gray -level 0%,100%,0.1 \
		-fuzz 10% -fill transparent -opaque "#BEBEBE" \
		-fuzz 16% -fill transparent -opaque white "$NAME-2.png"
done