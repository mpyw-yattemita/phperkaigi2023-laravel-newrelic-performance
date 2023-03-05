# Frontend

動作確認用の React + TypeScript 製のフロントエンドです。

```bash
# 差分を検知して自動コンパイル
npm run watch
```

**アセット配信サーバの役割はバックエンドに任せている**ので，このコマンドはコンパイル処理のみを行います。

<img width="829" alt="image" src="https://user-images.githubusercontent.com/1351893/222903921-48523b56-9bbf-439e-91bc-1a56314d1cf8.png">

- `LOAD` でデータをロードします。左側のオプションの状態に応じてどう読み込むかが変わります。
- `VALIDATE` でバリデーション， `UPDATE` でバリデーションと更新を行います。読み込まれた項目は `contenteditable` であるため，そのまま編集できます。

# Backend

以下の API を提供します。

- `GET /`
- `GET /api/view`
- `GET /api/validate`
- `GET /api/update`
