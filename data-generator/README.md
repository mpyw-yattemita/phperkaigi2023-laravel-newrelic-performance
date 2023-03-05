# data-generator

https://www.e-stat.go.jp/stat-search/file-download?statInfId=000032143614&fileKind=0 から得られる総務省の全国人口統計をネストした JSON に変換して取得します。

```ShellSession
❯ go run command/main.go --help
Usage of <main>:
  -pretty
      prints formatted JSON
```

```bash
# src/database/seeders 配下のシードデータを更新
go generate ./...
```
