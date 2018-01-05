using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Runtime.Serialization;
using System.Web;

namespace RestSem3SystemMockBeerTest.Models
{
    [DataContract]
    public class Beer
    {
        [DataMember]
        public int amount { get; set; }
        [DataMember]
        public int total { get; set; }
        [DataMember]
        public Int64 dateTime { get; set; }

        public Beer(int amount, int total, Int64 dateTime)
        {
            this.amount = amount;
            this.total = total;
            this.dateTime = dateTime;
        }
    }
}