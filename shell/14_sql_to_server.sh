#!/bin/sh
echo 'sqlファイルをサーバーに送信します。'

scp cb_red_book.sql amaraimusi@amaraimusi.sakura.ne.jp:www/cb_red_book/shell
echo "------------ 送信完了"
cmd /k