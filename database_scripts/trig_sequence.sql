


create or replace trigger trig_products
before insert on nepbuy_products
for each row 
begin 
:new.pk_product_id:= seq_products.nextval;
end;
/



create or replace trigger trig_carts
before insert on nepbuy_carts
for each row 
begin 
:new.pk_cart_id:= seq_carts.nextval;
end;
/

create or replace trigger trig_product_types
before insert on nepbuy_product_types
for each row 
begin 
:new.pk_product_type_id:= seq_product_types.nextval;
end;
/


create or replace trigger trig_shops
before insert on nepbuy_shops
for each row 
begin 
:new.pk_shop_id:= seq_shops.nextval;
end;
/

create or replace trigger trig_collection_slots
before insert on nepbuy_collection_slots
for each row 
begin 
:new.pk_collection_slot_id:= seq_collection_slots.nextval;
end;
/



create or replace trigger trig_collection_days
before insert on nepbuy_collection_days
for each row 
begin 
:new.pk_collection_day_id:= seq_collection_days.nextval;
end;
/


create or replace trigger trig_payment_info
before insert on nepbuy_payment_info
for each row 
begin 
:new.pk_payment__id:= seq_payment_info.nextval;
end;
/




create or replace trigger trig_orders
before insert on nepbuy_orders
for each row 
begin 
:new.pk_order_id:= seq_orders.nextval;
end;
/


create or replace trigger trig_collection_days_slots
before insert on nepbuy_collection_days_slots
for each row 
begin 
:new.pk_collection_day_slot_id:= seq_collection_days_slots.nextval;
end;
/


create or replace trigger trig_roles
before insert on nepbuy_roles
for each row 
begin 
:new.pk_role_id:= seq_roles.nextval;
end;
/



create or replace trigger trig_users
before insert on nepbuy_users 
for each row 
begin 
:new.pk_user_id:= seq_users.nextval;
end;
/




create or replace trigger trig_user_roles
before insert on nepbuy_user_roles
for each row 
begin 
:new.pk_user_role_id:= seq_user_roles.nextval;
end;
/
