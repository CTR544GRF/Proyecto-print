#*************************************************************
#*
#* Author :      SecuGen Corporation
#* Description : AUTO ON Makefile
#* Copyright(c): 2009 SecuGen Corporation, All rights reserved
#* date        person   comments
#* ======================================================
#* 12/10/2009  driley   initial release
#*
#*************************************************************

PORT = pi
CFLAGS = -DLINUX3
CPP=g++
INCPATH = -I./ -I../include
LIBS =  -L../lib 

FDU03_TARGET = ../bin/$(PORT)/auto_on_fdu03
FDU04_TARGET = ../bin/$(PORT)/auto_on_fdu04
FDU05_TARGET = ../bin/$(PORT)/auto_on_fdu05
FDU06_TARGET = ../bin/$(PORT)/auto_on_fdu06
FDU07_TARGET = ../bin/$(PORT)/auto_on_fdu07
FDU08_TARGET = ../bin/$(PORT)/auto_on_fdu08

all : $(FDU08_TARGET) $(FDU07_TARGET) $(FDU06_TARGET) $(FDU05_TARGET) $(FDU04_TARGET) $(FDU03_TARGET)

#Hamster Pro 20 (U20-A, U20-AP)
$(FDU08_TARGET) :
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU07 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu08 -lusb -lpthread 

#Hamster Pro 10 (U10)
$(FDU07_TARGET) :
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU07 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu07 -lusb -lpthread 
	
#Hamster Pro (UPx)
$(FDU06_TARGET) :
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU06 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu06 -lusb -lpthread 

#Hamster Pro 20 (U20)
$(FDU05_TARGET) :
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU05 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu05 -lusb -lpthread 

#Hamster IV (FDU04)
$(FDU04_TARGET) :
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU04 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu04 -lusb -lpthread 

#Hamster Plus (FDU03)
$(FDU03_TARGET) : 
	rm -rf $(PORT)/main.o
	$(CPP) $(CFLAGS) $(INCPATH) -D_FDU03 -o $@ main.cpp $(LIBS) -lsgfplib -lsgnfiq -lsgimage -lsgfpamx -lsgfdu03 -lusb -lpthread 

clean :
	rm -rf *.raw $(OBJS) $(FDU03_TARGET) $(FDU04_TARGET) $(FDU05_TARGET) $(FDU06_TARGET) $(FDU07_TARGET) core
	rm -rf $(PORT)
	mkdir $(PORT)

