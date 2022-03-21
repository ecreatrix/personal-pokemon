CSV="/Volumes/Files/_Business/Web/laravel/pokemon/_archive/pokedex/pokedex-filenames.csv"

sed 1d "$CSV"  | while read LINE; do
  NO=$(echo "$LINE" | cut -d$',' -f1)
  NAME=$(echo "$LINE" | cut -d$',' -f2 )
  FILENAME_NO=$(echo "$LINE" | cut -d$',' -f5 )
  FILENAME_NAME=$(echo "$LINE" | cut -d$',' -f6 )
  FILENAME_BOTH=$(echo "$LINE" | cut -d$',' -f7 )
  FILENAME_FORM=$(echo "$LINE" | cut -d$',' -f8 )

  #echo "$NO" - "$NAME" - "$FILENAME_NO" - "$FILENAME_NAME" - "$FILENAME_BOTH"
  #echo ""

  mv "$FILENAME_NO" "$FILENAME_BOTH"
  mv "$FILENAME_NAME" "$FILENAME_BOTH"
  mv "$FILENAME_FORM" "$FILENAME_BOTH"
done