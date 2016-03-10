drop sequence seq_products ;

create sequence seq_products
increment by 1
start with 1
nocache;

drop sequence seq_carts ;

create sequence seq_carts
increment by 1
start with 1
nocache;

drop sequence seq_product_types ;
create sequence seq_product_types
increment by 1
start with 1
nocache;

drop sequence seq_shops;
create sequence seq_shops
increment by 1
start with 1
nocache;


drop sequence seq_collection_slots ;
create sequence seq_collection_slots
increment by 1
start with 1
nocache;



drop sequence seq_collection_days;
create sequence seq_collection_days
increment by 1
start with 1
nocache;

drop sequence seq_collection_days_slots ;
create sequence seq_collection_days_slots
increment by 1
start with 1
nocache;


drop sequence seq_payment_info;
create sequence seq_payment_info
increment by 1
start with 1
nocache;


drop sequence seq_orders ;
create sequence seq_orders
increment by 1
start with 1
nocache;

drop sequence seq_roles ;
create sequence seq_roles
increment by 1
start with 1
nocache;





drop sequence seq_users ;
create sequence seq_users
increment by 1
start with 1
nocache;


drop sequence seq_user_roles ;
create sequence seq_user_roles
increment by 1
start with 1
nocache;