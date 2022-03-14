WGET="/Volumes/Poolz1 backup/aLYX/Pnp gaMes/pokemon/design/assets/pokemons/wget";
FOLDER="$WGET/bulba"

#mkdir "$FOLDER"/{test-bw,test-trimmed}

echo "• Trim"
cp "$FOLDER/"test/*.png "$FOLDER/"test-trimmed
cd "$FOLDER/"test-trimmed
find *.png \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"

	#echo NAME: "$NAME"
	###echo ""
	if [[ "$NAME" == *"00"* && "$NAME" != "00"* ]]; then
	  echo "$NAME"
	fi

	convert "$f" -trim "$NAME.png"
done

echo "• BW"
cp "$FOLDER/"test-trimmed/*.png "$FOLDER/"test-bw
cd "$FOLDER/"test-bw
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