# GitHub Actions Workflows

## Workflows

3 種類のワークフローを用意しています。

| ワークフロー                                          | `workflow_dispatch` | `push` | `schedule` | 備考                           |
|:------------------------------------------------|:-------------------:|:------:|:-----------|:-----------------------------|
| [`kubectl apply`](./kubectl.apply.yaml)         |          ✅          |        |            | 全て更新                         |
| [`kubectl set-image`](./kubectl.set-image.yaml) |          ✅          |   ✅    |            | Laravel をバンドルした PHP イメージのみ更新 | 
| [外形監視](./kick.yaml)                             |          ✅          |        | ✅          | New Relic の APM に記録させるためのもの  | 

## Variables and Secrets

以下のものを， GitHub Actions の Variables または Secrets として用意する必要があります。

### Variables

- `K8S_LARAVEL_APP_URL`
- `VITE_NEW_RELIC_URL`

### Secrets

- `IAM_ROLE_ARN`
- `K8S_CLUSTER_NAME`
- `K8S_IMAGE_REPOSITORY_PHP_LARAVEL`
- `K8S_NAMESPACE`
- `K8S_SECRET_BASE64_LARAVEL_ENCRYPTION_KEY`
- `K8S_SECRET_BASE64_MYSQL_PASSWORD`
- `K8S_SECRET_BASE64_MYSQL_ROOT_PASSWORD`
- `K8S_SECRET_BASE64_NEW_RELIC_LICENSE_KEY`
- `NEW_RELIC_API_KEY`
- `NEW_RELIC_DEPLOYMENT_ENTITY_GUID`
