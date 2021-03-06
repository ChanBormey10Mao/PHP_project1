SELECT CONCAT('Week ', WEEK(FROM_UNIXTIME(`sale_date`))) as week_number,
SUM(`sale_price`) as total 
FROM `sale`  
GROUP BY WEEK(FROM_UNIXTIME(`sale_date`));


SELECT SUM(sale_price) FROM sale GROUP BY WEEK(sale_date) 

SELECT SUM(sale_price),sale_date, WEEK(sale_date) as Week_No FROM sale GROUP BY WEEK(sale_date) 

SELECT ps.product_ID, SUM(s.sale_price), WEEK(s.sale_date) as Week_No 
FROM sale  as s 
INNER JOIN sale_product as ps
ON s.product_ID = ps.product_ID
GROUP BY WEEK(sale_date), ps.product_ID;

SELECT sale_product.product_ID, product.product_name, sale.sale_date,ROUND(sale_product.sale_PQuantity * product.Product_price,2) AS Price, WEEK(sale.sale_date) as Week_No 
FROM sale  
INNER JOIN sale_product
ON sale.sale_ID = sale_product.sale_ID
INNER JOIN product
ON sale_product.product_ID = product.product_ID
GROUP BY sale_product.product_ID
ORDER BY WEEK(sale.sale_date) 