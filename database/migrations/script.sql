ALTER TABLE `income` DROP INDEX `income_supplier_id_foreign`;
ALTER TABLE `income`
  DROP `voucher_number`,
  DROP `supplier_id`,
  DROP `voucher_type`;


ALTER TABLE 