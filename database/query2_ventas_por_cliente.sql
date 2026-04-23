-- Ventas por cliente

SELECT
    f.fecha_realizacion AS `Fecha Venta`,
    t.documento AS `Doc Cliente`,
    CONCAT(t.nombre, ' ', t.apellido) AS `Nombre Cliente`,

    -- Lista de facturas del cliente en esa fecha
    GROUP_CONCAT(
        CONCAT(f.fact_prefijo, '-', f.fact_consecutivo)
        ORDER BY f.fact_consecutivo ASC
        SEPARATOR ', '
    ) AS `Consecutivos`,

    -- Total de la venta
    SUM(fd.precio_unitario * fd.cantidad - fd.descuento) AS `Total Venta`,
    SUM(fd.cantidad) AS `Total Cantidad`,
    SUM(fd.iva) AS `Total IVA`

FROM facturacion f

INNER JOIN tercero t
    ON f.id_tercero = t.id_tercero

INNER JOIN facturacion_detalle fd
    ON fd.id_factura = f.id_factura

-- Solo facturas válidas
WHERE f.estado = 1
  AND f.fecha_cancelacion IS NULL

GROUP BY
    f.fecha_realizacion,
    t.id_tercero,
    t.documento,
    t.nombre,
    t.apellido

-- Orden por fecha y luego por mayor venta
ORDER BY
    f.fecha_realizacion ASC,
    `Total Venta` DESC;