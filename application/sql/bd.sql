drop table if exists ci_sessions cascade;

create table "ci_sessions" (
    "id" varchar(40) not null primary key,
    "ip_address" varchar(45) not null,
    "timestamp" bigint default 0 not null,
    "data" text default '' not null
);

create index "ci_sessions_timestamp" on "ci_sessions" ("timestamp");

drop table if exists usuarios cascade;
create table usuarios(
    id                  bigserial             constraint pk_usuarios primary key,
    nick                varchar(100) not null constraint uq_usuarios_nick unique,
    password            char(60)     not null constraint ck_password_valida
                                              check (length(password) = 60),
    email               varchar(100) not null,
    registro_verificado bool         not null default false,
    activado            bool         not null default true,
    latitud             double precision default null,
    longitud            double precision default null
);

drop table if exists usuarios_baneados cascade;
create table usuarios_baneados(
    usuario_id bigint constraint fk_usuarios_baneados_usuarios references usuarios (id),
    baneado bool not null default false,
    constraint pk_usuarios_baneados primary key (usuario_id)
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
    fecha     timestamp not null,
    precio money not null
);

drop table if exists etiquetas cascade;
create table etiquetas(
    id bigserial constraint pk_etiquetas primary key,
    nombre varchar(100) not null unique
);

drop table if exists etiquetas_articulos cascade;
create table etiquetas_articulos(
    etiqueta_id bigint constraint fk_etiquetas_articulos_etiquetas
                references etiquetas (id)
                on update cascade on delete cascade,
    articulo_id bigint constraint fk_etiquetas_articulos_articulos
                references articulos (id)
                on update cascade on delete cascade,
    constraint pk_etiquetas_articulos primary key (etiqueta_id, articulo_id)
);

drop table if exists ventas cascade;
create table ventas(
    id          bigserial constraint pk_ventas primary key,
    vendedor_id bigint constraint fk_ventas_usuarios_vendedor references usuarios (id)
                      on update cascade on delete cascade,
    comprador_id bigint constraint fk_ventas_usuarios_comprador references usuarios (id)
                      on update cascade on delete cascade,
    articulo_id bigint constraint fk_ventas_articulos references articulos (id)
                      on update cascade on delete cascade,
    fecha_venta date not null default current_date,
    constraint uq_ventas unique (vendedor_id, comprador_id, articulo_id)
);

-- Valoracion del vendedor al comprador
drop table if exists valoraciones_vendedor cascade;
create table valoraciones_vendedor (
    id bigserial constraint pk_valoraciones_vendedor primary key,
    venta_id bigint       constraint fk_valoraciones_vendedor_ventas references ventas (id)
                          on update cascade on delete cascade,
    valoracion numeric(1) constraint ck_valoraciones_max
                                        check (valoracion >= 0 AND valoracion <= 5),
    valoracion_text varchar(200),
    constraint uq_valoraciones_vendedor unique (venta_id)
);

-- Valoracion del comprador al vendedor
drop table if exists valoraciones_comprador cascade;
create table valoraciones_comprador (
    id bigserial constraint pk_valoraciones_comprador primary key,
    venta_id bigint       constraint fk_valoraciones_comprador_ventas references ventas (id)
                          on update cascade on delete cascade,
    valoracion numeric(1) constraint ck_valoraciones_max
                                        check (valoracion >= 0 AND valoracion <= 5),
    valoracion_text varchar(200),
    constraint uq_valoraciones_comprador unique (venta_id)
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
    fecha timestamp not null default current_timestamp,
    visto bool not null default false
);

insert into usuarios(nick, password, email, registro_verificado, activado, latitud, longitud)
    values('admin', crypt('admin', gen_salt('bf')), 'guillermo.lopez@iesdonana.org',
            true, true, null, null),
          ('guillermo', crypt('guillermo', gen_salt('bf')), 'guillermo.lopez@iesdonana.org',
            true, true, 36.7736776, -6.3529689),
          ('archsupremo', crypt('archsupremo', gen_salt('bf')), 'jdkdejava@gmail.com',
            true, true, 36.7795776, -6.3529689);

insert into articulos(nombre, descripcion, usuario_id, fecha, precio)
    values('Movil Xperia M4 Aqua', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Bicicleta', 'Semi nueva', 2, current_timestamp+'1 seconds', 50.3),
          ('Dragon Ball', 'Serie Completa', 3, current_timestamp+'2 seconds', 50.3),
          ('Dragon Ball Z', 'Serie Completa', 1, current_timestamp+'3 seconds', 50.3),
          ('Dragon Ball GT', 'Serie Completa', 2, current_timestamp+'4 seconds', 50.3),
          ('Dragon Ball Super', 'Serie Completa', 3, current_timestamp+'5 seconds', 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp+'6 seconds', 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp, 50.3),
          ('Mesa', 'Semi nueva', 2, current_timestamp+'7 seconds', 50.3),
          ('Portatil', 'Semi nuevo', 3, current_timestamp+'8 seconds', 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp+'9 seconds', 50.3),
          ('Mesa', 'Semi nueva', 2, current_timestamp+'10 seconds', 50.3),
          ('Portatil', 'Semi nuevo', 3, current_timestamp+'11 seconds', 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp+'12 seconds', 50.3),
          ('Mesa', 'Semi nueva', 2, current_timestamp+'13 seconds', 50.3),
          ('Portatil', 'Semi nuevo', 3, current_timestamp+'14 seconds', 50.3),
          ('Reloj', 'Semi nuevo', 1, current_timestamp+'15 seconds', 50.3),
          ('Mesa', 'Semi nueva', 2, current_timestamp+'16 seconds', 50.3),
          ('Portatil', 'Semi nuevo', 3, current_timestamp+'17 seconds', 50.3);

insert into etiquetas(nombre)
    values('Cocina'),
          ('Tecnologia'),
          ('Libros');

insert into etiquetas_articulos(etiqueta_id, articulo_id)
    values(2, 3),
          (3, 3),
          (2, 4),
          (3, 5);

insert into ventas(vendedor_id, comprador_id, articulo_id, fecha_venta)
    values(1, 2, 1, current_date),
          (2, 1, 2, current_date);

insert into valoraciones_vendedor(venta_id, valoracion, valoracion_text)
    values(1, 3, 'Gran comprador. Responsable y puntual. Lo recomiendo.');
/*
insert into valoraciones_comprador(vendedor_id_va, comprador_id_va, venta_id, valoracion, valoracion_text)
    values(2, 1, 1, 3, ''),
          (2, 1, 2, 5, '');
*/
insert into favoritos(usuario_id, articulo_id)
    values(2, 3),
          (2, 6),
          (3, 3);

insert into pm(emisor_id, receptor_id, mensaje, fecha, visto)
    values(1, 2, 'Primer Mensaje', current_timestamp, false),
          (3, 1, 'Segundo Mensaje', current_timestamp, false),
          (3, 1, 'Tercer Mensaje', current_timestamp, true),
          (3, 1, 'Cuarto Mensaje', current_timestamp, false);

create or replace function concat(text, text) returns text
    called on null input language plpgsql immutable
    as $$
        begin
            if $1 is null then
              return $2;
        end if;
            if $2 is null then
              return $1;
        end if;
    return $1 || $2;
end $$;
-- Linea fundamental la de a continuación, no quitar por nada del mundo
-- create aggregate text_concat (text) (sfunc = concat, stype = text);

drop view if exists v_etiquetas cascade;
create view v_etiquetas as
    select e.nombre, ea.articulo_id
    from etiquetas e join etiquetas_articulos ea
    on e.id = ea.etiqueta_id;

drop view if exists v_articulos_raw cascade;
create view v_articulos_raw as
    select a.*, u.nick, u.latitud, u.longitud, text_concat(e.nombre || ',') as etiquetas
    from articulos a join usuarios u on a.usuario_id = u.id
    left join v_etiquetas e on e.articulo_id = a.id
    group by a.id, a.nombre, a.descripcion,
             a.usuario_id, a.precio, a.fecha,
             u.nick, u.latitud, u.longitud;

drop view if exists v_ventas;
create view v_ventas as
 select v.id as venta_id, nombre, descripcion, precio,
        u.nick as comprador_nick, uu.nick as vendedor_nick,
        fecha_venta, articulo_id, vendedor_id, comprador_id,
        etiquetas
 from v_articulos_raw a join ventas v on a.id = v.articulo_id
      join usuarios u on u.id = v.comprador_id
      join usuarios uu on uu.id = v.vendedor_id;

drop view if exists v_articulos;
create view v_articulos as
    select *, id as articulo_id, FALSE as favorito
    from v_articulos_raw
    group by id, nombre, descripcion, usuario_id,
             fecha, precio, nick, etiquetas, latitud,
             longitud
    having id not in (select articulo_id from ventas)
    order by fecha desc;

drop view if exists v_etiquetas_articulos cascade;
create view v_etiquetas_articulos as
    select distinct on (a.articulo_id) a.articulo_id,
           a.nombre, a.etiquetas, usuario_id, fecha,
           precio, nick, a.favorito
    from v_etiquetas e join v_articulos a
    on a.articulo_id = e.articulo_id;

drop view if exists v_ventas_vendedor;
create view v_ventas_vendedor as
    select v.id as venta_id, nombre, descripcion, precio,
           u.nick as comprador_nick, uu.nick as vendedor_nick,
           fecha_venta, articulo_id, vendedor_id, comprador_id,
           valoracion, valoracion_text, etiquetas,
           vv.id as valoracion_id
    from v_articulos_raw a join ventas v on a.id = v.articulo_id
         join usuarios uu on uu.id = v.vendedor_id
         left join usuarios u on u.id = v.comprador_id
         left join valoraciones_vendedor vv on vv.venta_id = v.id;

drop view if exists v_ventas_comprador;
create view v_ventas_comprador as
    select v.id as venta_id, nombre, descripcion, precio,
           u.nick as comprador_nick, uu.nick as vendedor_nick,
           fecha_venta, articulo_id, vendedor_id, comprador_id,
           valoracion, valoracion_text, etiquetas,
           vv.id as valoracion_id
    from v_articulos_raw a join ventas v on a.id = v.articulo_id
         join usuarios u on u.id = v.comprador_id
         left join usuarios uu on uu.id = v.vendedor_id
         left join valoraciones_comprador vv on vv.venta_id = v.id;

drop view if exists v_usuarios_validados cascade;
create view v_usuarios_validados as
    select *
    from usuarios
    where registro_verificado = true;


drop view if exists v_favoritos cascade;
create view v_favoritos as
    select id as articulo_id, nombre, descripcion, a.usuario_id, precio,
             nick, f.usuario_id as usuario_favorito, TRUE as favorito,
             etiquetas
    from v_articulos a join favoritos f
    on a.id = f.articulo_id;

drop view if exists v_usuarios_localizacion cascade;
create view v_usuarios_localizacion as
    select u.id, u.nick, u.latitud, u.longitud,
           count(distinct a.articulo_id) as articulos_disponibles,
           count(distinct v.venta_id) as ventas,
           count(distinct vv.valoracion_id) + count(distinct vvv.valoracion_id) as valoraciones
    from usuarios u left join v_articulos a on u.id = a.usuario_id
         left join v_ventas v on u.id = v.vendedor_id
         left join v_ventas_comprador vv on u.id = vv.vendedor_id
         left join v_ventas_vendedor vvv on u.id = vvv.comprador_id
    where u.latitud is not NULL and u.longitud is not NULL
    group by u.id, u.nick, u.latitud, u.longitud;

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

drop view if exists v_usuarios_pm_vistos cascade;
create view v_usuarios_pm_vistos as
    select *
    from v_usuarios_pm
    where visto is true
    order by fecha_mensaje;

drop view if exists v_usuarios_pm_no_vistos cascade;
create view v_usuarios_pm_no_vistos as
    select *
    from v_usuarios_pm
    where visto is false
    order by fecha_mensaje;
