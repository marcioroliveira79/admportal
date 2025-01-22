/*==============================================================*/
/* DBMS name:      PostgreSQL 9.x                               */
/* Created on:     22/01/2025 16:55:07                          */
/*==============================================================*/


drop table PORTAL.FUNCIONALIDADE;

drop table PORTAL.LOG_ACESSO_USUARIO;

drop table PORTAL.LOG_ACESSO_USUARIO_FUNCIONALID;

drop table PORTAL.USUARIO;

drop user PORTAL;

/*==============================================================*/
/* User: PORTAL                                                 */
/*==============================================================*/
create user PORTAL;

/*==============================================================*/
/* Table: FUNCIONALIDADE                                        */
/*==============================================================*/
create table PORTAL.FUNCIONALIDADE (
   PK_FUNCIONALIDADE    SERIAL               not null,
   PK_USUARIO           INT4                 not null,
   DESCRICAO_FUNCIONALIDADE VARCHAR(1000)        not null,
   DESCRICAO_MENU       VARCHAR(50)          not null,
   CAMINHO_FUNCIONALIDADE VARCHAR(1000)        not null,
   DATA_CADASTRO        TIMESTAMP            not null,
   DATA_ALTERACAO       TIMESTAMP            null,
   constraint PK_FUNCIONALIDADE primary key (PK_FUNCIONALIDADE)
);

-- set table ownership
alter table PORTAL.FUNCIONALIDADE owner to PORTAL
;
/*==============================================================*/
/* Table: LOG_ACESSO_USUARIO                                    */
/*==============================================================*/
create table PORTAL.LOG_ACESSO_USUARIO (
   PK_LOG_USUARIO       SERIAL               not null,
   PK_USUARIO           INT4                 not null,
   DATA_LOGIN           TIMESTAMP            not null,
   DATA_LOGOUT          TIMESTAMP            null,
   constraint PK_LOG_ACESSO_USUARIO primary key (PK_LOG_USUARIO)
);

-- set table ownership
alter table PORTAL.LOG_ACESSO_USUARIO owner to PORTAL
;
/*==============================================================*/
/* Table: LOG_ACESSO_USUARIO_FUNCIONALID                        */
/*==============================================================*/
create table PORTAL.LOG_ACESSO_USUARIO_FUNCIONALID (
   PK_LOG_ACESSO_FUNCIONALIDADE SERIAL               not null,
   PK_USUARIO           INT4                 not null,
   PK_FUNCIONALIDADE    INT4                 not null,
   DATA_ACESSO          TIMESTAMP            not null,
   constraint PK_LOG_ACESSO_USUARIO_FUNCIONA primary key (PK_LOG_ACESSO_FUNCIONALIDADE)
);

-- set table ownership
alter table PORTAL.LOG_ACESSO_USUARIO_FUNCIONALID owner to PORTAL
;
/*==============================================================*/
/* Table: USUARIO                                               */
/*==============================================================*/
create table PORTAL.USUARIO (
   PK_USUARIO           SERIAL               not null,
   NOME                 VARCHAR(250)         not null,
   LOGIN                VARCHAR(250)         not null,
   SENHA                VARCHAR(30)          null,
   FK_USUARIO_ALTERACAO INT8                 null,
   DATA_BLOQUEIO        TIMESTAMP            null,
   DATA_CADASTRO        TIMESTAMP            not null,
   DATA_ALTERACAO       TIMESTAMP            null,
   constraint PK_USUARIO primary key (PK_USUARIO),
   constraint uk_login unique (LOGIN)
);

-- set table ownership
alter table PORTAL.USUARIO owner to PORTAL
;
alter table FUNCIONALIDADE
   add constraint FK_FUNCIONA_RF_USUARI_USUARIO foreign key (PK_USUARIO)
      references USUARIO (PK_USUARIO)
      on delete restrict on update restrict;

alter table LOG_ACESSO_USUARIO
   add constraint FK_LOG_ACES_RF_USUARI_USUARIO foreign key (PK_USUARIO)
      references USUARIO (PK_USUARIO)
      on delete restrict on update restrict;

alter table LOG_ACESSO_USUARIO_FUNCIONALID
   add constraint FK_LOG_ACES_RF_FUNCIO_FUNCIONA foreign key (PK_FUNCIONALIDADE)
      references FUNCIONALIDADE (PK_FUNCIONALIDADE)
      on delete restrict on update restrict;

alter table LOG_ACESSO_USUARIO_FUNCIONALID
   add constraint FK_LOG_ACES_RF_USUARI_USUARIO foreign key (PK_USUARIO)
      references USUARIO (PK_USUARIO)
      on delete restrict on update restrict;

