# ThinkPHP项目部署到Vercel指南

## 部署准备

已完成以下配置：

1. 创建了`vercel.json`配置文件，指定了PHP运行时和路由规则
2. 创建了`api/index.php`作为Vercel的入口文件

## 部署步骤

1. 安装Vercel CLI（如果尚未安装）
   ```bash
   npm install -g vercel
   ```

2. 登录Vercel（如果尚未登录）
   ```bash
   vercel login
   ```

3. 在项目根目录下运行部署命令
   ```bash
   vercel
   ```

4. 按照提示完成部署配置
   - 选择要部署的项目
   - 确认项目设置
   - 等待部署完成

5. 部署到生产环境（可选）
   ```bash
   vercel --prod
   ```

## 配置说明

### vercel.json

```json
{
  "$schema": "https://openapi.vercel.sh/vercel.json",
  "functions": {
    "api/*.php": {
      "runtime": "vercel-php@0.7.3"
    }
  },
  "routes": [
    { "src": "/(.*)", "dest": "/api/index.php" }
  ],
  "buildCommand": "composer install --no-dev"
}
```

- `functions`: 指定PHP运行时环境
- `routes`: 将所有请求重定向到入口文件
- `buildCommand`: 部署时执行的构建命令

### api/index.php

这是Vercel的入口文件，负责初始化ThinkPHP应用并处理HTTP请求。

## 注意事项

1. Vercel主要支持无服务器函数和静态网站托管，某些ThinkPHP功能可能需要调整
2. 文件系统操作应限制在临时目录，因为Vercel的文件系统是只读的
3. 数据库连接需要使用外部数据库服务
4. 确保PHP版本兼容性（Vercel支持PHP 7.4-8.3）

## 故障排除

如果部署后遇到问题：

1. 检查Vercel部署日志
2. 确认`composer.json`中的依赖项正确
3. 验证项目结构符合ThinkPHP要求
4. 检查PHP版本兼容性

## 参考资源

- [Vercel PHP Runtime](https://github.com/vercel-community/php)
- [ThinkPHP官方文档](https://www.thinkphp.cn/doc/)
- [Vercel部署文档](https://vercel.com/docs/deployments)