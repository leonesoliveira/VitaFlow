INSERT INTO considera_final_semana (id,descricao) VALUES (1,'Sim'); 

INSERT INTO considera_final_semana (id,descricao) VALUES (2,'Não'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (1,5,'5 Minutos antes de iniciar','inicia em 5 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (2,10,'10 Minutos antes de iniciar','inicia em 10 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (3,15,'15 Minutos antes de iniciar','inicia em 15 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (4,20,'20 Minutos antes de iniciar','inicia em 20 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (5,30,'30 Minutos antes de iniciar','inicia em 30 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (6,45,'45 Minutos antes de iniciar','inicia em 45 minutos.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (7,60,'1 Hora antes de iniciar','inicia em 1 hora.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (8,120,'2 Hora antes de iniciar','inicia em 2 horas.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (9,180,'3 Hora antes de iniciar','inicia em 3 horas.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (10,240,'4 Hora antes de iniciar','inicia em 4 horas.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (11,300,'5 Hora antes de iniciar','inicia em 5 horas.'); 

INSERT INTO lembrete_agenda (id,minutos,descricao,mensagem) VALUES (12,360,'6 Hora antes de iniciar','inicia em 6 horas.'); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (1,'Sem Recorrência',0); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (2,'Diário',1); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (3,'Semanal',7); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (4,'Mensal',30); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (5,'Anual',365); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (6,'Quinzenal',15); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (7,'Trimestral',90); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (8,'Semestral',180); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (9,'Dia Específico',0); 

INSERT INTO recorrencia (id,descricao,qtd_dia) VALUES (10,'Personalizada',0); 

INSERT INTO relevancia (id,descricao,cor) VALUES (1,'Importante','#ffa000'); 

INSERT INTO relevancia (id,descricao,cor) VALUES (2,'Urgente','#d50000'); 

INSERT INTO relevancia (id,descricao,cor) VALUES (3,'Eventual','#4caf50'); 

INSERT INTO saldo_calculado (id,descricao) VALUES (1,'Sim'); 

INSERT INTO saldo_calculado (id,descricao) VALUES (2,'Não'); 

INSERT INTO status_lancamento (id,descricao) VALUES (1,'Aberto'); 

INSERT INTO status_lancamento (id,descricao) VALUES (2,'Pago'); 

INSERT INTO status_tarefa (id,descricao,cor) VALUES (1,'Pendente','#ffcdd2'); 

INSERT INTO status_tarefa (id,descricao,cor) VALUES (2,'Em Progresso','#fff9c4'); 

INSERT INTO status_tarefa (id,descricao,cor) VALUES (3,'Concluída','#a5d6a7'); 

INSERT INTO system_group (id,name,uuid) VALUES (1,'Admin',null); 

INSERT INTO system_group (id,name,uuid) VALUES (2,'Standard',null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (1,1,1,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (2,1,2,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (3,1,3,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (4,1,4,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (5,1,5,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (6,1,6,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (7,1,8,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (8,1,9,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (9,1,11,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (10,1,14,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (11,1,15,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (12,2,10,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (13,2,12,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (14,2,13,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (15,2,16,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (16,2,17,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (17,2,18,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (18,2,19,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (19,2,20,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (20,1,21,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (21,2,22,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (22,2,23,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (23,2,24,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (24,2,25,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (25,1,26,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (26,1,27,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (27,1,28,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (28,1,29,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (29,2,30,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (30,1,31,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (31,1,32,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (32,1,33,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (33,1,34,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (34,1,35,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (35,1,36,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (36,1,37,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (37,1,38,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (38,1,39,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (39,1,40,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (40,1,41,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (41,1,42,null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (1,'System Group Form','SystemGroupForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (2,'System Group List','SystemGroupList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (3,'System Program Form','SystemProgramForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (4,'System Program List','SystemProgramList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (5,'System User Form','SystemUserForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (6,'System User List','SystemUserList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (7,'Common Page','CommonPage',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (8,'System PHP Info','SystemPHPInfoView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (9,'System ChangeLog View','SystemChangeLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (10,'Welcome View','WelcomeView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (11,'System Sql Log','SystemSqlLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (12,'System Profile View','SystemProfileView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (13,'System Profile Form','SystemProfileForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (14,'System SQL Panel','SystemSQLPanel',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (15,'System Access Log','SystemAccessLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (16,'System Message Form','SystemMessageForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (17,'System Message List','SystemMessageList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (18,'System Message Form View','SystemMessageFormView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (19,'System Notification List','SystemNotificationList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (20,'System Notification Form View','SystemNotificationFormView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (21,'System Document Category List','SystemDocumentCategoryFormList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (22,'System Document Form','SystemDocumentForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (23,'System Document Upload Form','SystemDocumentUploadForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (24,'System Document List','SystemDocumentList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (25,'System Shared Document List','SystemSharedDocumentList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (26,'System Unit Form','SystemUnitForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (27,'System Unit List','SystemUnitList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (28,'System Access stats','SystemAccessLogStats',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (29,'System Preference form','SystemPreferenceForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (30,'System Support form','SystemSupportForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (31,'System PHP Error','SystemPHPErrorLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (32,'System Database Browser','SystemDatabaseExplorer',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (33,'System Table List','SystemTableList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (34,'System Data Browser','SystemDataBrowser',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (35,'System Menu Editor','SystemMenuEditor',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (36,'System Request Log','SystemRequestLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (37,'System Request Log View','SystemRequestLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (38,'System Administration Dashboard','SystemAdministrationDashboard',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (39,'System Log Dashboard','SystemLogDashboard',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (40,'System Session dump','SystemSessionDumpView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (41,'Files diff','SystemFilesDiff',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (42,'System Information','SystemInformationView',null); 

INSERT INTO system_unit (id,name,connection_name) VALUES (1,'Matriz','matriz'); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (1,1,1); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (2,2,2); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (3,1,2); 

INSERT INTO system_user_program (id,system_user_id,system_program_id) VALUES (1,2,7); 

INSERT INTO system_users (id,name,login,password,email,frontpage_id,system_unit_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','admin@admin.net',10,null,'Y','','',null,null,null); 

INSERT INTO system_users (id,name,login,password,email,frontpage_id,system_unit_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (2,'User','user','ee11cbb19052e40b07aac0ca060c23ee','user@user.net',7,null,'Y','','',null,null,null); 

INSERT INTO system_user_unit (id,system_user_id,system_unit_id) VALUES (1,1,1); 

INSERT INTO tipo (id,descricao) VALUES (1,'Pessoal'); 

INSERT INTO tipo (id,descricao) VALUES (2,'Profissional'); 

INSERT INTO tipo_categoria (id,descricao) VALUES (1,'Receita'); 

INSERT INTO tipo_categoria (id,descricao) VALUES (2,'Despesa'); 

INSERT INTO tipo_conta (id,descricao) VALUES (1,'Conta Corrente'); 

INSERT INTO tipo_conta (id,descricao) VALUES (2,'Conta Poupança'); 

INSERT INTO tipo_conta (id,descricao) VALUES (3,'Investimento'); 

INSERT INTO tipo_conta (id,descricao) VALUES (4,'Digital'); 

INSERT INTO tipo_lancamento (id,descricao) VALUES (1,'Receita'); 

INSERT INTO tipo_lancamento (id,descricao) VALUES (2,'Despesa'); 

INSERT INTO totaliza_receita_despesa (id,descricao) VALUES (1,'Sim'); 

INSERT INTO totaliza_receita_despesa (id,descricao) VALUES (2,'Não'); 
