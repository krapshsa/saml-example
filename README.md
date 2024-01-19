# 啟動 keycloak
```
docker compose up -d
```

# 啟動專案
```
php -S localhost:9980
```

# 開始測試
1. 新增 keycloak realm `test`
2. 在 `test` 中新增 client `ssp`
3. 設定 Master SAML Processing URL `http://localhost:9980/acs`
4. 設定對 Assertion 進行簽名
5. 將 `Realm Settings > Keys` 中的 Certificate 放到 `settings.json` 中的
