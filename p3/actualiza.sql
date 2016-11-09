--			APARTADO A
--
--		Restricciones
--
-- alter table bets
-- add constraint fk_
-- foreign key ()
-- references other_table (pkey)
-- on delete cascade


-- clientorders customerid no es fkey, orderid no es pkey
alter table clientorders
add constraint pk_orders
primary key (orderid); 

alter table clientorders
add constraint fk_customerid
foreign key (customerid)
references customers(customerid)
on delete cascade; -- Si se borra un usuario, 
                   -- se borran los carritos

-- bets Cumple restricciones
-- clientbets orderid no es foreign key. Customer id es redundante
alter table clientbets
add constraint fk_orderid
foreign key (orderid)
references clientorders(orderid)
on delete cascade;

alter table clientbets
drop column customerid;

-- customers Check en cada atributo
alter table customers
add constraint check_all
check (
	(zip similar to '[0-9]{5}')
	and (email ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$')
	and (age > 0));


--			APARTADO B
--
--		Actualizacion atributo category
--

create table categories (
	categoryid serial primary key,
	categorystring varchar
);

alter table bets
add column categoryid integer references categories(categoryid);

alter table options
add column categoryid integer references categories(categoryid);

insert into categories (categorystring)
 	select distinct category
	from bets
	union
	select distinct categoria as category
	from options;

update bets
set categoryid = categories.categoryid 
from categories 
where categories.categorystring = bets.category;

update options
set categoryid = categories.categoryid 
from categories 
where categories.categorystring = options.categoria;

alter table bets
drop column category;

alter table options
drop column categoria;
