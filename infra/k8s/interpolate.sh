#!/usr/bin/env bash

set -e

# ファイル自身のディレクトリに移動
cd "$(dirname "${BASH_SOURCE[0]}")"

# 動的に生成する ConfigMap
kubectl create configmap nginx-conf --from-file=../docker/nginx/nginx.cloud.conf --dry-run=client -o=yaml

# 複数のマニフェストファイルを --- で連結し
# envsubst にて "K8S_" をプレフィクスに持つ環境変数のみを置換
# 置換結果を適用
#
# shellcheck disable=SC2016,SC2086
find . -name '*.yaml' -print0 \
| xargs -0 awk 'FNR==1 {print "---"}{print}' \
| envsubst "$(printf '${%s} ' ${!K8S_*})"
