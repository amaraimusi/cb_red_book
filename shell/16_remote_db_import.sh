#!/bin/sh

echo 'サーバー側のシェルを実行します。'
ssh -l amaraimusi amaraimusi.sakura.ne.jp "
	sh www/cb_red_book/shell/server/db_import.sh;
	"

echo "サーバー側のシェルをすべて実行しました。"
cmd /k