##mogrify -fill none -fuzz 33% -draw 'alpha 0,0 floodfill' -flop  -draw 'alpha 0,0 floodfill' -flop -format png *.jpg
##rm *.jpg

mkdir -p _tmp
#rm _tmp/*

find * \! -name ".DS_Store" -type f -maxdepth 0 | while read f; do
	NAME=$(basename "$f")
	NAME="${NAME%.*}"
	TEMP="_tmp/$NAME"

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
	convert "$f" \
	\( -clone 0 -negate -threshold 5% -type bilevel +write "$TEMP-master_mask_before".gif \
	-define connected-components:area-threshold=30 \
	-define connected-components:mean-color=true \
	-connected-components 4 +write "$TEMP-master_mask_after".gif \) \
	-alpha off -compose copy_opacity -composite \
	"$TEMP-finished.png"

	convert "$TEMP-finished.png" -trim +repage "$f"
done

