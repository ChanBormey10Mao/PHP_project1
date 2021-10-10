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