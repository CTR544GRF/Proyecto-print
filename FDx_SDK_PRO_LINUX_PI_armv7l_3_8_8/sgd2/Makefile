#*************************************************************
#*
#* Author :      SecuGen Corporation
#* Description : SGD2 Makefile
#* Copyright(c): 2004 SecuGen Corporation, All rights reserved
#* History :
#* date        person   comments
#* ======================================================
#*
#*
#*************************************************************
PORT = pi
CPP=g++
##CFLAGS = -D__LINUX -D__cplusplus -fno-stack-protector
#CFLAGS = -D__LINUX -D__cplusplus -Wall -O
##INCPATH = -I./ -I../include `gtk-config --cflags`
##LIBS =  -L../lib/$(PORT) `gtk-config --libs`

#GTK1.0##########################
#CFLAGS = -D__LINUX -fno-stack-protector
#INCPATH = -I./ -I../include `gtk-config --cflags`
#LIBS =  -L../lib/$(PORT) `gtk-config --libs`
#################################
#GTK1.2##########################
CFLAGS = -D__LINUX -D__GTK12 -fno-stack-protector
INCPATH = -I./ -I../include `pkg-config --cflags gtk+-2.0`
LIBS =  -L../lib/$(PORT) `pkg-config --libs gtk+-2.0`
#################################


OBJS=	sgd2.o \
	image.o

SRCS=	sgd2.cpp \
	image.cpp

FDU03_TARGET = ../bin/$(PORT)/sgd2_fdu03
FDU04_TARGET = ../bin/$(PORT)/sgd2_fdu04
FDU05_TARGET = ../bin/$(PORT)/sgd2_fdu05
FDU06_TARGET = ../bin/$(PORT)/sgd2_fdu06
FDU07_TARGET = ../bin/$(PORT)/sgd2_fdu07
FDU08_TARGET = ../bin/$(PORT)/sgd2_fdu08

all : $(FDU08_TARGET) $(FDU07_TARGET) $(FDU06_TARGET) $(FDU05_TARGET) $(FDU04_TARGET) $(FDU03_TARGET)

#Hamster Pro 20 (U20-A, U20-AP)
$(FDU08_TARGET) : $(OBJS)
	$(CPP) -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu08 -lpthread  -lusb

#Hamster Pro 10 (U10)
$(FDU07_TARGET) : $(OBJS)
	$(CPP) -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu07 -lpthread  -lusb

#Hamster Pro (UPx)
$(FDU06_TARGET) : $(OBJS)
	$(CPP) -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu06 -lpthread  -lusb

#Hamster Pro 20 (U20)
$(FDU05_TARGET) : $(OBJS)
	$(CPP) -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu05 -lpthread  -lusb

#Hamster IV (FDU04)
$(FDU04_TARGET) : $(OBJS)
	$(CPP)  -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu04 -lpthread  -lusb

#Hamster Plus (FDU03)
$(FDU03_TARGET) : $(OBJS)
	$(CPP) -o $@ $(OBJS) $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu03 -lpthread  -lusb

.cc.o :
	$(CPP) $(CFLAGS) $(INCPATH) -c $<

.c.o :
	$(CPP) $(CFLAGS) $(INCPATH) -c $<

.cpp.o :
	$(CPP) $(CFLAGS) $(INCPATH) -c $<

dep :
	gccmakedep $(INCPATH) $(SRCS)

clean :
	rm -rf *.raw *.min $(OBJS) $(FDU07_TARGET) $(FDU06_TARGET) $(FDU05_TARGET) $(FDU04_TARGET) $(FDU03_TARGET) core
	rm -rf $(PORT)
	mkdir $(PORT)

