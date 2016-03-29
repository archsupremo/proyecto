drop table if exists ci_sessions cascade;

create table "ci_sessions" (
    "id" varchar(40) not null primary key,
    "ip_address" varchar(45) not null,
    "timestamp" bigint default 0 not null,
    "data" text default '' not null
);

create index "ci_sessions_timestamp" on "ci_sessions" ("timestamp");

drop table if exists categorias cascade;
create table categorias(
    id bigserial constraint pk_categorias primary key,
    nombre varchar(100) not null unique
);

drop table if exists usuarios cascade;
create table usuarios(
    id                  bigserial             constraint pk_usuarios primary key,
    nick                varchar(100) not null constraint uq_usuarios_nick unique,
    password            char(60)     not null constraint ck_password_valida
                                              check (length(password) = 60),
    email               varchar(100) not null,
    registro_verificado bool         not null default false,
    rol_id              bigint       not null default 2 constraint fk_usuarios_roles
                                                        references roles(id)
                                                        on delete no action
                                                        on update cascade,
    activado            bool         not null default true,
    baneado             bool         not null default false
);

drop table if exists articulos cascade;
create table articulos(
    id bigserial constraint pk_articulos primary key,
    nombre varchar(100) not null,
    descripcion varchar(500),
    usuario_id bigint constraint fk_articulos_usuarios references usuarios (id)
                      on update cascade on delete cascade,
    categoria_id bigint constraint fk_articulos_categorias references categorias (id)
                        on update cascade on delete cascade,
    precio money not null
);

insert into usuarios(nick, password, email, registro_verificado, rol_id, activado)
    values('admin', crypt('admin', gen_salt('bf')), 'guillermo.lopez@iesdonana.org', true, 1, true),
          ('guillermo', crypt('guillermo', gen_salt('bf')), 'guillermo.lopez@iesdonana.org', true, 2, true);

insert into categorias(nombre)
    values('Cocina'),
          ('Deporte'),
          ('Tecnologia'),
          ('Libros');

insert into articulos(nombre, descripcion, usuario_id, categoria_id, precio)
    values('Movil Xperia M4 Aqua', 'Semi nuevo', 2, 3, 50.3),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 2, 1, 12.5),
          ('Cancion de Hielo Y Fuego: Juego de Tronos', 'Semi nuevo', 2, 4, 150.3);

drop view if exists v_articulos;

create view v_articulos as
    select a.*, u.nick, c.nombre as nombre_categoria
    from articulos a join usuarios u on a.usuario_id = u.id
         join categorias c on a.categoria_id = c.id;
