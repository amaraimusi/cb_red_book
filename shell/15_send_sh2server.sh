#!/bin/sh
echo 'サーバー側のshファイルをサーバーに送信します。'

scp -r server amaraimusi@amaraimusi.sakura.ne.jp:www/cb_red_book/shell/

echo 'サーバー側のshファイルをサーバーに送信しました。'
cmd /k