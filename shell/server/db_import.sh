#!/bin/bash

echo 'さくらスタンダードのコントロールパネルで先にデータベースを作成してくだい'
echo "DBパスワードを入力してください"
read pw

mysql -h mysql716.db.sakura.ne.jp -u amaraimusi -p$pw -B amaraimusi_cb_red_book < www/cb_red_book/shell/cb_red_book.sql
echo "出力完了"