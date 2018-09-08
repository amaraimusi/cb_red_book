#!/bin/sh

echo '作業ディレクトリ'
pwd

echo "ローカルDBのパスワードを入力してください"
read pw

echo 'SQLをエクスポートします。'
mysqldump -uroot -p$pw cb_red_book > cb_red_book.sql
echo 'エクスポートしました。'

echo 'SQLファイルをサーバーに転送します。'
scp cb_red_book.sql amaraimusi@amaraimusi.sakura.ne.jp:www/cb_red_book/shell
echo '転送しました。'

echo "------------ 終わり"
cmd /k