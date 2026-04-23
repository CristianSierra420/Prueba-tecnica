-- Productos más vendidos

SELECT
    fd.sku AS `Código de Producto`,
    SUM(fd.cantidad) AS `Total Cantidades`,
    SUM(fd.precio_unitario * fd.cantidad) AS `Total Venta`,
    SUM(fd.costo_unitario * fd.cantidad) AS `Total Costo`

FROM facturacion_detalle fd

-- Solo toma facturas activas y no canceladas
INNER JOIN facturacion f
    ON fd.id_factura = f.id_factura
   AND f.estado = 1
   AND f.fecha_cancelacion IS NULL

GROUP BY fd.sku

-- Ordena de mayor a menor cantidad vendida
ORDER BY `Total Cantidades` DESC;