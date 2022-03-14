WGET="/Volumes/Poolz1 backup/aLYX/Pnp gaMes/pokemon/design/assets/pokemons/wget";
FOLDER="$WGET/portal-pokemon-trans"

cd "$FOLDER/"
#cp "$WGET/"pokedb/*.jpg "$WGET/"pokedb-trans

##mogrify -fill none -fuzz 33% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop -format png *.jpg
##rm *.jpg

mkdir -p "$FOLDER/"_tmp
rm "$FOLDER/"_tmp/*

find "$FOLDER/"/* \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"
	TEMP="$FOLDER/_tmp/$NAME"

	echo NAME: "$NAME"
	###echo ""

	##convert "$f" -fill none -fuzz 1% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-1.png"
	##convert "$f" -fill none -fuzz 2% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-2.png"
	##convert "$f" -fill none -fuzz 3% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-3.png"
	##convert "$f" -fill none -fuzz 4% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-4.png"
	##convert "$f" -fill none -fuzz 5% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-5.png"
	##convert "$f" -fill none -fuzz 7% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-7.png"
	##convert "$f" -fill none -fuzz 9% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-9.png"
	##convert "$f" -fill none -fuzz 11% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-11.png"
	##convert "$f" -fill none -fuzz 13% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-13.png"
	##convert "$f" -fill none -fuzz 15% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-15.png"
	##convert "$f" -fill none -fuzz 18% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-18.png"
	##convert "$f" -fill none -fuzz 20% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-20.png"
	##convert "$f" -fill none -fuzz 25% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-25.png"
	##convert "$f" -fill none -fuzz 28% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-28.png"
	##convert "$f" -fill none -fuzz 31% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-31.png"
	##convert "$f" -fill none -fuzz 33% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-33.png"
	##convert "$f" -fill none -fuzz 35% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP-35.png"

	#start removing background
	convert "$f" -fill none -fuzz 1% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop "$TEMP.png"

	convert "$f" -alpha remove +repage \
		\( +clone -fx 'p{0,0}' \) -compose Difference -composite  -modulate 100,0 +matte "$TEMP-difference.png"
	# remove the black, replace with transparency
	convert "$TEMP-difference.png" -fuzz 1% -bordercolor white -border 1 -fill none -draw "alpha 0,0 floodfill" -shave 1x1 "$TEMP-removed-black.png"
	# create the matte
	convert "$TEMP-removed-black.png" -channel matte -separate +matte -blur 0x1 "$TEMP-matte.png"
	# you are going for: white interior, black exterior
	composite -compose CopyOpacity "$TEMP-matte.png" "$TEMP.png" "$TEMP-finished.png"
	convert "$TEMP-finished.png" -trim +repage "$NAME-transparent.png"
done
