CREATE TABLE IF NOT EXISTS student (
    id bigserial PRIMARY KEY,
    full_name varchar(150) not null,
    birth_year int not null,
    address varchar null
);