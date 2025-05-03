# HTTP/2 支持问题修复说明

## 问题描述

在 Vercel 环境中运行时，出现以下错误：

```
https://deltaforce.drluo.top/qq/sig
HTTP/2 is not supported by the stream handler.
```

这个错误表明在 Vercel 环境中，应用尝试使用 HTTP/2 协议，但当前的流处理器不支持 HTTP/2。

## 解决方案

我们采取了以下措施来解决这个问题：

### 1. 修改 QQ 控制器中的 HTTP 客户端配置

在 `app/controller/QQ.php` 文件中，移除了强制使用 HTTP/2 版本的设置：

```php
// 修改前
$this->client = new Client([
    'cookies' => $this->cookie,
    'allow_redirects' => false,
    'verify' => false,
    'version' => 2.0,  // 强制使用 HTTP/2
]);

// 修改后
$this->client = new Client([
    'cookies' => $this->cookie,
    'allow_redirects' => false,
    'verify' => false,
    // 移除 HTTP/2 版本设置，使用默认 HTTP 处理器
    // 'version' => 2.0,
]);
```

### 2. 更新 composer.json 依赖

在 `composer.json` 文件中，添加了 curl 扩展的依赖：

```json
"require": {
    "php": ">=8.0.0",
    "topthink/framework": "^8.0",
    "topthink/think-orm": "^3.0|^4.0",
    "topthink/think-filesystem": "^2.0",
    "guzzlehttp/guzzle": "^7.9",
    "ext-curl": "*"
},
```

### 3. 配置 vercel.json

更新了 `vercel.json` 文件，添加了 PHP 扩展和构建环境配置：

```json
{
  "$schema": "https://openapi.vercel.sh/vercel.json",
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.3",
      "includeFiles": "**/*.php",
      "excludeFiles": "{test/**,vendor/bin/**}"
    }
  },
  "routes": [
    { "src": "/(.*)", "dest": "/api/index.php" }
  ],
  "build": {
    "env": {
      "PHP_EXTENSIONS": "curl"
    }
  }
}
```

## 部署说明

1. 确保已更新以上三个文件：
   - `app/controller/QQ.php`
   - `composer.json`
   - `vercel.json`

2. 在部署到 Vercel 前，运行以下命令更新依赖：
   ```bash
   composer update
   ```

3. 将更新后的代码推送到 Git 仓库，然后在 Vercel 上重新部署项目。

4. 部署完成后，检查 Vercel 的构建日志，确保 curl 扩展已正确安装。

## 注意事项

- 如果在 Vercel 环境中仍然遇到 HTTP/2 相关问题，可能需要考虑使用其他 HTTP 客户端库或进一步配置 Vercel 环境。
- 确保 Vercel 的 PHP 运行时版本与项目要求兼容。
- 对于本地开发环境，如果需要使用 HTTP/2，请确保本地 PHP 环境已安装并启用了相应的扩展。