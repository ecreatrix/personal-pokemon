WGET="/Volumes/Poolz1 backup/aLYX/Pnp gaMes/pokemon/design/assets/pokemons/wget";
FOLDER="$WGET/official"

mkdir "$FOLDER/"{colour,bw}

cp "$FOLDER/"original/*.jpg "$FOLDER/"colour
cp "$FOLDER/"original/*.png "$FOLDER/"colour

cd "$FOLDER/colour"
mogrify -fill none -fuzz 33% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop -format png *.jpg
rm *.jpg

find "$FOLDER/colour"/* \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"

	#echo NAME: "$NAME"
	###echo ""

	convert "$f" -trim "$NAME.png"
done
