DELIMITER $$

DROP PROCEDURE IF EXISTS HW9PROC$$
CREATE PROCEDURE HW9PROC(in city char(50),in discnt_decrease integer)
BEGIN
    UPDATE CUSTOMERS
    SET discnt = discnt - discnt_decrease
    WHERE CUSTOMERS.city = city; 
    UPDATE CUSTOMERS
    SET discnt = 0
    WHERE CUSTOMERS.city = city AND discnt_decrease<0;
END$$
