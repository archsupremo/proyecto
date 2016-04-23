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
    baneado             bool         not null default false,
    latitud             double precision default null,
    longitud            double precision default null
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
    fecha_venta date not null,
    valoracion_id bigint constraint fk_ventas_valoraciones references valoraciones (id)
                  on update cascade on delete cascade,
    constraint pk_ventas primary key (vendedor_id, comprador_id, articulo_id)
);

drop table if exists valoraciones cascade;
create table valoraciones (
    id bigserial constraint pk_valoraciones primary key,
    usuario_valorado bigint    constraint fk_usuarios_valorado references usuarios (id)
                          on update cascade on delete cascade,
    usuario_valorador  bigint    constraint fk_usuarios_valorador references usuarios (id)
                          on update cascade on delete cascade,
    valoracion  numeric(1) constraint ck_valoraciones_max
                                        check (valoracion >= 1 AND valoracion <= 5)
);

drop table if exists favoritos cascade;
create table favoritos(
    usuario_id bigint constraint fk_favoritos_usuarios references usuarios (id)
                      on update cascade on delete cascade,
    articulo_id bigint constraint fk_favoritos_articulos references articulos (id)
                      on update cascade on delete cascade,
    constraint pk_favoritos primary key (usuario_id, articulo_id)
);

drop table if exists pm cascade;
create table pm(
    id bigserial constraint pk_pm primary key,
    emisor_id bigint constraint fk_pm_usuarios_emisor references usuarios (id)
                             on update cascade on delete cascade,
    receptor_id bigint constraint fk_pm_usuarios_receptor references usuarios (id)
                               on update cascade on delete cascade,
    mensaje varchar(500) not null,
    fecha timestamp not null,
    visto bool not null default false
);

insert into usuarios(nick, password, email, registro_verificado, rol_id, activado, latitud, longitud)
    values('admin', crypt('admin', gen_salt('bf')), 'guillermo.lopez@iesdonana.org',
            true, 1, true, null, null),
          ('guillermo', crypt('guillermo', gen_salt('bf')), 'guillermo.lopez@iesdonana.org',
            true, 2, true, 36.7726, -6.3530),
          ('archsupremo', crypt('archsupremo', gen_salt('bf')), 'jdkdejava@gmail.com',
            true, 2, true, 36.7730, -6.3530);

insert into categorias(nombre)
    values('Cocina'),
          ('Deporte'),
          ('Tecnologia'),
          ('Libros');

insert into articulos(nombre, descripcion, usuario_id, categoria_id, precio)
    values('Movil Xperia M4 Aqua', 'Semi nuevo', 2, 3, 50.3),
          ('Cuchillo para cortar verdura de Guillermo 1', 'Semi nuevo', 2, 1, 12.5),
          ('Cancion de Hielo Y Fuego: Juego de Tronos', 'Semi nuevo', 2, 4, 150.3),
          ('Cuchillo para cortar verdura de archsupremo 1', 'Semi nuevo', 3, 1, 12.5),
          ('Cuchillo para cortar verdura de admin 1', 'Semi nuevo', 1, 1, 12.5),
          ('Cuchillo para cortar verdura de archsupremo 2', 'Semi nuevo', 3, 1, 12.5),
          ('Cuchillo para cortar verdura de admin 2', 'Semi nuevo', 1, 1, 12.5);

insert into valoraciones(usuario_valorado, usuario_valorador, valoracion)
    values(2, 1, 3),
          (2, 1, 5);

insert into ventas(vendedor_id, comprador_id, articulo_id, fecha_venta, valoracion_id)
    values(2, 1, 1, current_date, 1),
          (2, 3, 2, current_date, 2);

insert into favoritos(usuario_id, articulo_id)
    values(2, 3),
          (2, 6),
          (3, 3);

insert into pm(emisor_id, receptor_id, mensaje, fecha, visto)
    values(1, 2, 'Primer Mensaje', current_timestamp, false),
          (3, 1, 'Segundo Mensaje', current_timestamp, false),
          (3, 1, 'Tercer Mensaje', current_timestamp, true),
          (3, 1, 'Cuarto Mensaje', current_timestamp, false);

drop view if exists v_articulos cascade;
create view v_articulos_raw as
    select a.*, u.nick, c.nombre as nombre_categoria
    from articulos a join usuarios u on a.usuario_id = u.id
         join categorias c on a.categoria_id = c.id;

drop view if exists v_ventas;
create view v_ventas as
    select nombre, descripcion, nombre_categoria, precio, a.nick as nick_comprador,
           u.nick as comprador_nick, fecha_venta as fecha_venta, articulo_id,
           vendedor_id, categoria_id, comprador_id, valoracion
    from v_articulos_raw a join ventas v on a.id = v.articulo_id
         join usuarios u on u.id = v.comprador_id join valoraciones va
         on v.valoracion_id = va.id;

drop view if exists v_articulos;
create view v_articulos as
    select *, FALSE as favorito
    from v_articulos_raw
    group by id, nombre, descripcion, usuario_id, categoria_id, precio,
             nick, nombre_categoria
    having id not in (select articulo_id from v_ventas);

drop view if exists v_usuarios_validados cascade;
create view v_usuarios_validados as
    select *
    from usuarios
    where registro_verificado = true;


drop view if exists v_favoritos cascade;
create view v_favoritos as
    select id, nombre, descripcion, a.usuario_id, categoria_id, precio,
             nick, nombre_categoria, f.usuario_id as usuario_favorito, TRUE as favorito
    from v_articulos a join favoritos f
    on a.id = f.articulo_id;

drop view if exists v_usuarios_localizacion cascade;
create view v_usuarios_localizacion as
    select *
    from usuarios
    where latitud is not NULL and longitud is not NULL;

drop view if exists v_usuarios_pm cascade;
create view v_usuarios_pm as
    select p.*, u.nick as nick_emisor, u.email as email_emisor,
           us.nick as nick_receptor, us.email as email_receptor,
           to_char(fecha, 'DD-MM-YYYY HH24:MI') as fecha_mensaje
    from pm p join usuarios u
         on p.emisor_id = u.id
         join usuarios us
         on p.receptor_id = us.id
    order by visto asc;
