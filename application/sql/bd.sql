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

drop table if exists tokens cascade;

create table tokens (
    usuario_id bigint   constraint pk_tokens primary key
                        constraint fk_tokens_usuarios references usuarios (id),
    token      char(32) not null
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

drop table if exists ventas;
create table ventas(
    vendedor_id bigint constraint fk_ventas_usuarios_vendedor references usuarios (id)
                      on update cascade on delete cascade,
    comprador_id bigint constraint fk_ventas_usuarios_comprador references usuarios (id)
                      on update cascade on delete cascade,
    articulo_id bigint constraint fk_ventas_articulos references articulos (id)
                      on update cascade on delete cascade,
    fecha date not null,
    constraint pk_ventas primary key (vendedor_id, comprador_id, articulo_id)
);

drop table if exists valoraciones cascade;
create table valoraciones (
    articulo_id bigint    constraint fk_articulos_valoraciones references articulos (id)
                          on update cascade on delete cascade,
    usuario_id  bigint    constraint fk_usuarios_valoraciones references usuarios (id)
                          on update cascade on delete cascade,
    valoracion  numeric(1) constraint ck_valoraciones_max
                                        check (valoracion >= 1 AND valoracion <= 5),
    constraint pk_valoraciones primary key (articulo_id, usuario_id)
);

insert into usuarios(nick, password, email, registro_verificado, rol_id, activado)
    values('admin', crypt('admin', gen_salt('bf')), 'guillermo.lopez@iesdonana.org', true, 1, true),
          ('guillermo', crypt('guillermo', gen_salt('bf')), 'guillermo.lopez@iesdonana.org', true, 2, true),
          ('archsupremo', crypt('archsupremo', gen_salt('bf')), 'jdkdejava@gmail.com', true, 2, true);

insert into categorias(nombre)
    values('Cocina'),
          ('Deporte'),
          ('Tecnologia'),
          ('Libros');

insert into articulos(nombre, descripcion, usuario_id, categoria_id, precio)
    values('Movil Xperia M4 Aqua', 'Semi nuevo', 2, 3, 50.3),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 2, 1, 12.5),
          ('Cancion de Hielo Y Fuego: Juego de Tronos', 'Semi nuevo', 2, 4, 150.3),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 3, 1, 12.5),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 1, 1, 12.5),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 3, 1, 12.5),
          ('Cuchillo para cortar verdura', 'Semi nuevo', 1, 1, 12.5);

drop view if exists v_articulos cascade;

create view v_articulos as
    select a.*, u.nick, c.nombre as nombre_categoria
    from articulos a join usuarios u on a.usuario_id = u.id
         join categorias c on a.categoria_id = c.id;

drop view if exists v_usuarios_validados cascade;

create view v_usuarios_validados as
    select *
    from usuarios
    where registro_verificado = true;
