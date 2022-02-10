CREATE TABLE CLIENTS(
client_id INT AUTO_INCREMENT UNIQUE,
client_email VARCHAR(100),
password VARCHAR(255),
full_name VARCHAR(100),
telephone_number DECIMAL(12),
shipping_address VARCHAR(255),
CONSTRAINT PK_ClientEmail PRIMARY KEY (client_email)
);

CREATE TABLE ARTISTS(
artist_email VARCHAR(100),
date_of_birth DATE,
full_name VARCHAR(100),
total_created_artworks DECIMAL(2),
style VARCHAR(50),
CONSTRAINT PK_ArtistEmail PRIMARY KEY (artist_email)
);

CREATE TABLE ARTWORKS(
title VARCHAR(100),
artist_email VARCHAR(100),
url VARCHAR(255),
topics VARCHAR(50),
starting_date DATE,
ending_date DATE,
available_quantity DECIMAL(2),
created_quantity DECIMAL (2),
dimension_x DECIMAL(3),
dimension_y DECIMAL(3),
price DECIMAL(8,2),
CONSTRAINT PK_Artworks PRIMARY KEY (title,artist_email),
CONSTRAINT FK_Artworks_ArtistEmail FOREIGN KEY (artist_email) REFERENCES ARTISTS(artist_email)
);

CREATE TABLE ORDERS(
order_id INT(5) AUTO_INCREMENT,
client_email VARCHAR(100),
total_charge DECIMAL(8,2),
order_date DATE,
total_artworks_adquired DECIMAL(2),
status VARCHAR(100),
CONSTRAINT PK_Orders PRIMARY KEY (order_id),
CONSTRAINT FK_Orders_ClientEmail FOREIGN KEY (client_email) REFERENCES CLIENTS(client_email)
);

CREATE TABLE PURCHASED_ARTWORKS(
artwork_title VARCHAR(100),
artist_email VARCHAR(100),
order_id INT(5),
price_by_unit DECIMAL(6,2),
units DECIMAL(2),
CONSTRAINT PK_PurchasedArtworks PRIMARY KEY (artwork_title,artist_email,order_id),
CONSTRAINT FK_PurchasedArtworks_Title FOREIGN KEY (artwork_title) REFERENCES ARTWORKS(title),
CONSTRAINT FK_PurchasedArtworks_OrderId FOREIGN KEY (order_id) REFERENCES ORDERS(order_id),
CONSTRAINT FK_PurchasedArtworks_ArtistEmail FOREIGN KEY (artist_email) REFERENCES ARTWORKS(artist_email)
);