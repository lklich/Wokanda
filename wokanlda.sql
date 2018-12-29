USE [wokanda]
GO

/****** Tworzenie bazy MS SQL - wokanda ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[wokanda](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[numer_sali] [char](10) NULL,
	[data_rozprawy] [date] NULL,
	[godzina_start] [time](7) NULL,
	[godzina_stop] [time](7) NULL,
	[aktywny] [bit] NULL,
	[wydzial] [varchar](250) NULL,
	[dzien_tyg]  AS ([dbo].[DzienTygodnia]([data_rozprawy])),
	[pozycja] [varchar](250) NULL,
	[sygnatura] [varchar](50) NULL,
	[przewodniczacy] [varchar](250) NULL,
	[protokolant] [varchar](250) NULL,
	[prokurator] [varchar](250) NULL,
	[obroncy] [varchar](max) NULL,
	[oskarzeni] [varchar](max) NULL,
	[czy_maloletni] [bit] NULL,
	[lawnicy] [varchar](500) NULL,
	[strony_inne] [varchar](max) NULL,
	[obowiazek] [bit] NULL,
	[wynik] [varchar](250) NULL,
	[sesja_poczatek] [time](7) NULL,
	[zakonczona] [bit] NULL,
	[sesja_koniec] [time](7) NULL,
	[przedmiot] [varchar](250) NULL,
	[oskarzyciel] [varchar](250) NULL,
	[rep_oskarzyciela] [varchar](50) NULL,
	[tryb] [varchar](150) NULL,
	[kwalifikacja] [varchar](max) NULL,
	[strony] [varchar](max) NULL,
	[biegli] [nvarchar](max) NULL,
	[wydz] [nchar](10) NULL,
	[stamptime] [smalldatetime] NULL,
	[czyjawna] [bit] NULL,
 CONSTRAINT [PK_wokanda] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_aktywny]  DEFAULT ((1)) FOR [aktywny]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_czy_maloletni]  DEFAULT ((0)) FOR [czy_maloletni]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_obowiazek]  DEFAULT ((0)) FOR [obowiazek]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_zakonczona]  DEFAULT ((0)) FOR [zakonczona]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_stamptime]  DEFAULT (getdate()) FOR [stamptime]
GO

ALTER TABLE [dbo].[wokanda] ADD  CONSTRAINT [DF_wokanda_czyjawna]  DEFAULT ((1)) FOR [czyjawna]
GO
