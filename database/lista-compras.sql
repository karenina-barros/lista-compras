CREATE DATABASE IF NOT EXISTS lista_compras 
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

USE lista_compras;

CREATE USER 'app_user'@'%' IDENTIFIED BY '123';

GRANT ALL PRIVILEGES ON lista_compras . * TO 'app_user'@'%'; 

CREATE TABLE produtos(
	id int not null primary key auto_increment,
	nome varchar(191) not null
);

CREATE TABLE lojas(
	id int not null primary key auto_increment,
	nome varchar(191) not null
);

CREATE TABLE unidades_medida(
	id int not null primary key auto_increment,
	nome varchar(191) not null
);

CREATE TABLE lista_compras(
	id int not null primary key auto_increment,
	id_produto int not null,
	id_loja int not null,
	id_unidade_medida int not null,
	quantidade double(10,2) not null,
	preco double(10,2) not null,
	data date not null
);

CREATE TABLE compras(
	id int not null primary key auto_increment,
	id_produto int not null,
	id_loja int not null,
	id_unidade_medida int not null,
	quantidade double(10,2) not null,
	preco double(10,2) not null,
	data date not null
);

ALTER TABLE lista_compras
ADD CONSTRAINT FK_produtos_lista_compras
FOREIGN KEY (id_produto) REFERENCES produtos(id);

ALTER TABLE lista_compras
ADD CONSTRAINT FK_lojas_lista_compras
FOREIGN KEY (id_loja) REFERENCES lojas(id);

ALTER TABLE lista_compras
ADD CONSTRAINT FK_unidades_medida_lista_compras
FOREIGN KEY (id_unidade_medida) REFERENCES unidades_medida(id);

ALTER TABLE compras
ADD CONSTRAINT FK_produtos_compras
FOREIGN KEY (id_produto) REFERENCES produtos(id);

ALTER TABLE compras
ADD CONSTRAINT FK_lojas_compras
FOREIGN KEY (id_loja) REFERENCES lojas(id);

ALTER TABLE compras
ADD CONSTRAINT FK_unidades_medida_compras
FOREIGN KEY (id_unidade_medida) REFERENCES unidades_medida(id);
