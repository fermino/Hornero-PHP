# Hornero-PHP

## Uso

Registrate en http://hornero.fi.uncoma.edu.ar e ingresá en algún torneo. Iniciá una consola (cmd en windows, bash en linux) y navegá hasta el directorio de Hornero. 

Para configurar Hornero con los datos que te da la página una vez registrado, hacé: 

```
php Hornero.php -n <Nombre del equipo> -t <Token del torneo>
```

Luego, crea tu script en problems/<ID>.php, donde <ID> es el número identificador del torneo. Los datos están en la variable `$Parameters` (array) y debes ubicar la solución en `$Solution`. Mirá el script de ejemplo (problems/1.php). Para ejecutarlo y enviar la solución, hacé: 

```
php Hornero.php <ID>
```

Si quieres ver los parametros que devuelve el servidor, puedes hacer: 

```
php Hornero.php -d <ID>
```

Enjoy :)