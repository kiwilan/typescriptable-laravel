# Test environment

## SQL Server

```bash
docker run -e "ACCEPT_EULA=Y" -e "MSSQL_SA_PASSWORD=12345OHdf%e" \
  -p 1433:1433 --name sqlserver --hostname sqlserver \
  -d \
  mcr.microsoft.com/mssql/server:2022-latest
```
